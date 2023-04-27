<script>
	var listener = new window.keypress.Listener();

	listener.simple_combo("f6", function() {
		console.log('f6')
		livewire.emit('saveSale')
	})
	listener.simple_combo("shift t", function() {
		console.log('f6')
		document.querySelector('#addSearchAll').click();
        document.getElementById('modal-search-input').value =''
	})

    listener.simple_combo("f8", function() {
        document.getElementById('cash').value =''
        document.getElementById('cash').focus()
        document.getElementById('hiddenTotal').value =''
    })

    listener.simple_combo("shift a", function() {
        document.getElementById('modal-search-input').value =''
        document.getElementById('modal-search-input').focus()
		$("#modalSearchProduct").modal("show");
	})

    listener.simple_combo("shift x", function() {
        document.getElementById('modal-search-input').value =''
		$("#modalSearchProduct").modal("hide");
	})


	listener.simple_combo("shift s", function() {
		document.getElementById('modal-search-input').value =''
		document.getElementById('modal-search-input').focus()
	})

	listener.simple_combo("f7", function() {
		console.log('print last : f7')
		livewire.emit('print-last')
	})

	listener.simple_combo("f4", function() {
		var total = parseFloat(document.getElementById('hiddenTotal').value)
		if(total > 0) {
			Confirm(0, 'clearCart', '¿SEGUR@ DE ELIMINAR EL CARRITO?')
		} else
		{
			noty('AGREGA PRODUCTOS A LA VENTA')
		}
	})



</script>
