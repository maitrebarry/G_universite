
   document.addEventListener('DOMContentLoaded', function() {
var deleteButtons = document.querySelectorAll('.delete-button');
deleteButtons.forEach(function(button) {
    button.addEventListener('click', function(event) {
        event.preventDefault();
        var idToDelete = this.getAttribute('href');
        Swal.fire({
            title: "Êtes-vous sûr?",
            text: "Vous pourrez annuler cette action!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Oui, supprimer!",
            cancelButtonText: "Annuler"
        }).then((result) => {
          if (result.isConfirmed) {
                        Swal.fire({
                            title: "Deleted!",
                            text: "Votre suppression a été faite avec succès.",
                            icon: "success"
                        }).then(() => {
                            window.location.href = idToDelete;
                        });
                    }
        });
    });
});
});
