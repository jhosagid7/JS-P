<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\Sale;
use App\Models\User;
use App\Models\Company;

use App\Models\SaleDetail;
use Mike42\Escpos\Printer;
use Illuminate\Http\Request;
use Mike42\Escpos\EscposImage;
use Illuminate\Support\Facades\DB;
use Mike42\Escpos\CapabilityProfile;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;


class PrinterController extends Controller
{

    public $print_error = 0;
    public $print_name;
    protected $machine_user;
    protected $machine_pass;
    protected $machine_name;
    protected $network;
    protected $print_route;


    public function ticketSale(Request $request){
        $folio = str_pad($request->id, 7, "0", STR_PAD_LEFT);
        // $this->print_name = "BIXOLON-SRP-350plus";
        $this->print_name = "AnyDesk Printer";
        $this->machine_user = "Administrator";
        $this->machine_pass = "1avacamar1posa";
        $this->machine_name = "DESKTOP-DSUA32T";
        $this->network = true;


        $connector = new WindowsPrintConnector($this->print_name);
        $printer = new Printer($connector);

        $sale_details = DB::table('sale_details as sd')
        ->join('products as p', 'p.id', '=', 'sd.product_id')
        ->select('p.name as products', 'sd.quantity', 'sd.price')
        ->where('sd.sale_id', '=', $request->id)->get();

        $sales = DB::table('sales as s')
        ->join('users as u', 'u.id', '=', 's.user_id')
        ->where('s.id', '=', $request->id)->get();

        $companies = Company::all();

        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->setTextSize(2,2);
        $printer->text(strtoupper($companies->name));
        $printer->setTextSize(1, 1);
        $printer->text("** Nota de Entrega **" . "\n");
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("========================================================" . "\n");
        $printer->text("Nº     :                                      " . $folio . "\n");
        $printer->text("FECHA: " . Carbon::parse($sales->created_at)->format('d/m/Y') . "                      HORA: " . Carbon::parse($sales->created_at)->format('h:m:s') . "\n");
        $printer->setEmphasis(true);
        $printer->text("UD      DESCRIPCION                            Precio   " . "\n");
        $printer->setEmphasis(false);
        $printer->text("--------------------------------------------------------" . "\n");

        //creamos un contador
        $cont = 0;
        $nombre = '';

        foreach ($sale_details as $details) {
            $nombre = $sale_details->quantity . " " . $sale_details->products;
            $data = $this->numCodigo($nombre, " $." . $sale_details->price);
            $printer->text($data . " \n");
        }

        $printer->text("--------------------------------------------------------" . "\n");

        $printer->setJustification();

        $printer->setEmphasis(true);
        $printer->setPrintLeftMargin(0);

        $h = $printer->setPrintLeftMargin(350);
        $printer->text("TOTAL: $." . $sales->total . "\n");
        $printer->text("PAGADO:    $." . $sales->cash . "\n");
        $printer->text("CAMBIO:    $." . $sales->change . "\n");
        $printer->setEmphasis(true);

        //footer
        $printer->selectPrintMode();
        $printer->setBarcodeHeight(80);
        $printer->barcode($folio, Printer::BARCODE_CODE39);
        $printer->feed(2);

        $printer->setJustification();
        $printer->setPrintLeftMargin(0);
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("Gracias por su compra \n");

        $printer->feed(3);
        $printer->cut();
        $printer->pulse();
        $printer->close();
    }

    public function ticketLastSale(){

        return 'ok...';

        dd('ok...');
    }

    //este metodo nos permite dar formato al numero de la factura
    public function numCodigo($nombre, $id_cod)
    {
        if (empty($id_cod)) {
            throw new Exception('!La funcion numCodigo esperaba 3 parametros y uno no fue dado...');
            exit;
        } else {
            $longitud       = strlen($id_cod);
            $resta          = '-' . $longitud;
            $resul_num      = substr_replace('.......................................................', $id_cod, $resta);
            $num_Codigo     = strtoupper($resul_num);
            $nombre =  substr($nombre, 0, 42);
            $num_Codigo     = $this->numCodigoLeft($num_Codigo, $nombre);

            // $num_Codigo     = strtoupper($sucursal . '-' . $op . $resul_num);
            return $num_Codigo;
        }
    }







}
