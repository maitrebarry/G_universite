document.addEventListener("DOMContentLoaded", function () {
  const dateDebutInput = document.getElementById("date_debut");
  const dateFinInput = document.getElementById("date_fin");

  // Empêcher la sélection de dates passées pour la date de début
  const today = new Date().toISOString().split("T")[0];
  dateDebutInput.setAttribute("min", today);

  dateDebutInput.addEventListener("change", function () {
    const dateDebutValue = new Date(this.value);
    const dateFinValue = new Date(dateDebutValue);
    let daysAdded = 0;

    // Ajouter 7 jours en excluant le dimanche
    while (daysAdded < 7) {
      dateFinValue.setDate(dateFinValue.getDate() + 1);
      if (dateFinValue.getDay() !== 0) {
        // Exclure le dimanche
        daysAdded++;
      }
    }

    // Définir la date de fin par défaut et la limite minimale
    dateFinInput.value = dateFinValue.toISOString().split("T")[0];
    dateFinInput.setAttribute(
      "min",
      new Date(dateDebutValue.getTime() + 24 * 60 * 60 * 1000)
        .toISOString()
        .split("T")[0]
    ); // La date de fin doit être au moins le jour suivant la date de début
  });

  dateFinInput.addEventListener("input", function () {
    // Empêcher la sélection de dates passées pour la date de fin
    const selectedDate = new Date(this.value);
    const minDate = new Date(dateDebutInput.value);

    if (selectedDate <= minDate) {
      // Afficher une alerte ou un message d'erreur si la date de fin est égale ou antérieure à la date de début
      alert("La date de fin doit être après la date de début.");
      this.value = "";
    }
  });
});
