<?php

namespace App\Traits;

use Exception;
use Carbon\Carbon;
use App\Models\Sale;
use App\Models\User;
use App\Sessioncaja;
use App\Models\Company;
use App\Models\SaleDetail;
use Mike42\Escpos\Printer;
use Illuminate\Http\Request;

use Mike42\Escpos\EscposImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Mike42\Escpos\CapabilityProfile;

use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;



trait PrintTrait
{
    public $print_error = 0;
    public $print_name;
    protected $machine_user;
    protected $machine_pass;
    protected $machine_name;
    protected $network;
    protected $print_route;


    public function ticketSale($sale_ID)
    {
        try {
            $folio = str_pad($sale_ID, 7, "0", STR_PAD_LEFT);
            // $this->print_name = "BIXOLON-SRP-350plus";
            $this->print_name = "AnyDesk Printer";
            $this->machine_user = "Administrator";
            $this->machine_pass = "1avacamar1posa";
            $this->machine_name = "DESKTOP-DSUA32T";
            $this->network = false;

            if ($this->network) {
                $this->print_route = "smb://$this->machine_user:$this->machine_pass@$this->machine_name/$this->print_name";
            } else {
                $this->print_route = $this->print_name;
            }

            $connector = new WindowsPrintConnector($this->print_route);


            // $connector = new WindowsPrintConnector($print_name);
            $printer = new Printer($connector);


            $sale_details = DB::table('sale_details as sd')
            ->join('products as p', 'p.id', '=', 'sd.product_id')
            ->select('p.name as products', 'sd.quantity', 'sd.price')
            ->where('sd.sale_id', '=', $sale_ID)->get();

            $sales = DB::table('sales as s')
            ->join('users as u', 'u.id', '=', 's.user_id')
            ->where('s.id', '=', $sale_ID)->get();
            // return $sales;

            $companies = Company::all();

            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setTextSize(2, 2);
            $printer->text(strtoupper($companies[0]->name));
            $printer->setTextSize(1, 1);
            $printer->text("** Nota de Entrega **" . "\n");
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("========================================================" . "\n");
            $printer->text("Nº     :                                      " . $folio . "\n");
            $printer->text("FECHA: " . Carbon::parse($sales[0]->created_at)->format('d/m/Y') . "                      HORA: " . Carbon::parse($sales[0]->created_at)->format('h:m:s') . "\n");
            $printer->setEmphasis(true);
            $printer->text("UD      DESCRIPCION                            Precio   " . "\n");
            $printer->setEmphasis(false);
            $printer->text("--------------------------------------------------------" . "\n");

            //creamos un contador
            $cont = 0;
            $nombre = '';

            foreach ($sale_details as $details) {
                $nombre = $details->quantity . " " . $details->products;
                $data = $this->numCodigo($nombre, " $." . $details->price);
                $printer->text($data . " \n");
            }

            $printer->text("--------------------------------------------------------" . "\n");

            $printer->setJustification();

            $printer->setEmphasis(true);
            $printer->setPrintLeftMargin(0);

            $h = $printer->setPrintLeftMargin(350);
            $printer->text("TOTAL: $." . $sales[0]->total . "\n");
            $printer->text("PAGADO:    $." . $sales[0]->cash . "\n");
            $printer->text("CAMBIO:    $." . $sales[0]->change . "\n");
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

            $this->print_error = 1;

        } catch (Exception $e) {

            $this->print_error = 0;


        }
    }

    public function ticketLastSale($sale_ID)
    {
        try {
            $folio = str_pad($sale_ID, 7, "0", STR_PAD_LEFT);
            // $this->print_name = "BIXOLON-SRP-350plus";
            $this->print_name = "AnyDesk Printer";
            $this->machine_user = "Administrator";
            $this->machine_pass = "1avacamar1posa";
            $this->machine_name = "DESKTOP-DSUA32T";
            $this->network = true;

            if ($this->network) {
                $this->print_route = "smb://$this->machine_user:$this->machine_pass@$this->machine_name/$this->print_name";
            } else {
                $this->print_route = $this->print_name;
            }

            $connector = new WindowsPrintConnector($this->print_route);


            // $connector = new WindowsPrintConnector($print_name);
            $printer = new Printer($connector);


            $sale_details = DB::table('sale_details as sd')
            ->join('products as p', 'p.id', '=', 'sd.product_id')
            ->select('p.name as products', 'sd.quantity', 'sd.price')
            ->where('sd.sale_id', '=', $sale_ID)->get();

            $sales = DB::table('sales as s')
            ->join('users as u', 'u.id', '=', 's.user_id')
            ->where('s.id', '=', $sale_ID)->get();
            // return $sales;

            $companies = Company::all();

            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setTextSize(2, 2);
            $printer->text(strtoupper($companies[0]->name));
            $printer->setTextSize(1, 1);
            $printer->text("** Nota de Entrega **" . "\n");
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("========================================================" . "\n");
            $printer->text("Nº     :                                      " . $folio . "\n");
            $printer->text("FECHA: " . Carbon::parse($sales[0]->created_at)->format('d/m/Y') . "                      HORA: " . Carbon::parse($sales[0]->created_at)->format('h:m:s') . "\n");
            $printer->setEmphasis(true);
            $printer->text("UD      DESCRIPCION                            Precio   " . "\n");
            $printer->setEmphasis(false);
            $printer->text("--------------------------------------------------------" . "\n");

            //creamos un contador
            $cont = 0;
            $nombre = '';

            foreach ($sale_details as $details) {
                $nombre = $details->quantity . " " . $details->products;
                $data = $this->numCodigo($nombre, " $." . $details->price);
                $printer->text($data . " \n");
            }

            $printer->text("--------------------------------------------------------" . "\n");

            $printer->setJustification();

            $printer->setEmphasis(true);
            $printer->setPrintLeftMargin(0);

            $h = $printer->setPrintLeftMargin(350);
            $printer->text("TOTAL: $." . $sales[0]->total . "\n");
            $printer->text("PAGADO:    $." . $sales[0]->cash . "\n");
            $printer->text("CAMBIO:    $." . $sales[0]->change . "\n");
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

            $this->print_error = 1;
        } catch (Exception $e) {
            $this->print_error = 0;

        }
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

    public function numCodigoLeft($string, $nombre)
    {
        if (empty($string) || empty($nombre)) {
            throw new Exception('!La funcion numCodigo esperaba 3 parametros y uno no fue dado...');
            exit;
        } else {
            $longitud       = strlen($nombre);
            $resta          = '-' . $longitud;
            $resul_num      = substr_replace($string, $nombre, 0, $longitud);
            $num_Codigo     = strtoupper($resul_num);
            // $num_Codigo     = strtoupper($sucursal . '-' . $op . $resul_num);
            return $num_Codigo;
        }
    }
}
