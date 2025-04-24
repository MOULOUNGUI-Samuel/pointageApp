(function ($) {
 "use strict";
 
	/*----------------------
		Dialogs
	 -----------------------*/

	//Basic
	$('#sa-basic').on('click', function(){
		swal("Here's a message!");
	});

	//A title with a text under
	$('#sa-title').on('click', function(){
		swal("Here's a message!", "Lorem ipsum dolor cry sit amet, consectetur adipiscing elit. Sed lorem erat, tincidunt vitae ipsum et, Spensaduran pellentesque maximus eniman. Mauriseleifend ex semper, lobortis purus.")
	});

	//Success Message
	$('#sa-success').on('click', function(){
		swal("Good job!", "Lorem ipsum dolor cry sit amet, consectetur adipiscing elit. Sed lorem erat, tincidunt vitae ipsum et, Spensaduran pellentesque maximus eniman. Mauriseleifend ex semper, lobortis purus.", "success")
	});

	//Warning Message
	$(document).ready(function () {
		// Cibler tous les boutons avec la classe
		$('.sa-warning-btn').on('click', function () {
			const userId = $(this).data('id'); // récupère l'ID si besoin
	
			Swal.fire({
				title: "Êtes-vous sûr ?",
				text: "Cette action est irréversible.",
				icon: "warning",
				showCancelButton: true,
				confirmButtonColor: "#d33",
				cancelButtonColor: "#3085d6",
				confirmButtonText: "Oui, supprimer",
				cancelButtonText: "Annuler"
			}).then((result) => {
				if (result.isConfirmed) {
					// Tu peux ici soumettre un formulaire, ou faire un fetch/ajax
					Swal.fire("Supprimé !", "L'action a bien été effectuée.", "success");
	
					// Exemple : soumettre un formulaire lié
					// $('#form-delete-' + userId).submit();
				}
			});
		});
	});
	
	
	//Parameter
	$('#sa-params').on('click', function(){
		swal({   
			title: "Are you sure?",   
			text: "You will not be able to recover this imaginary file!",   
			type: "warning",   
			showCancelButton: true,   
			confirmButtonText: "Yes, delete it!",
			cancelButtonText: "No, cancel plx!",   
		}).then(function(isConfirm){
			if (isConfirm) {     
				swal("Deleted!", "Your imaginary file has been deleted.", "success");   
			} else {     
				swal("Cancelled", "Your imaginary file is safe :)", "error");   
			} 
		});
	});

	//Custom Image
	$('#sa-image').on('click', function(){
		swal({   
			title: "Sweet!",   
			text: "Here's a custom image.",   
			imageUrl: "img/dialog/like.png" 
		});
	});

	//Auto Close Timer
	$('#sa-close').on('click', function(){
		 swal({   
			title: "Auto close alert!",   
			text: "I will close in 2 seconds.",   
			timer: 2000
		});
	});

 
})(jQuery); 