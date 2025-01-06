$(document).ready(function () {
  const $form = $(".form");
  if ($form.length) {
    const $fields = {
      statut: $("#statut"),
      enseignant: $("#enseignant_select"),
      filiere: $("#filiere"),
      semestre: $("#semestre"),
      date_debut: $("#date_debut"),
      date_fin: $("#date_fin"),
      nh_programme: $("#nh_programme"),
      heures_supp: $("#heures_supp"),
      grade: $("#grade"),
      heures_dues: $("#heures_dues"),
    };

    $form.on("submit", function (event) {
      let hasError = false;

      // Réinitialiser toutes les erreurs
      resetErrors($fields);

      // Validation des champs
      $.each($fields, function (key, field) {
        if (!isValidField(field, $fields.statut.val())) {
          displayError(field, key);
          hasError = true;
        }
      });

      if (hasError) {
        event.preventDefault();
      }
    });

    // Réinitialiser les erreurs lors de la modification des champs
    $.each($fields, function (key, field) {
      const eventType = field.prop("tagName") === "SELECT" ? "change" : "input";
      field.on(eventType, function () {
        resetError(field);
      });
    });
  }

  function resetErrors(fields) {
    $.each(fields, function (key, field) {
      resetError(field);
    });
  }

  function resetError(field) {
    if (field.length) {
      field.removeClass("is-invalid");
      const error = $(`#${field.attr("id")}_error`);
      if (error.length) {
        error.text("");
      }
    }
  }

  function isValidField(field, statut) {
    if (
      !field.length ||
      (statut === "2" &&
        (field.attr("id") === "grade" || field.attr("id") === "heures_dues"))
    )
      return true;
    if (field.prop("tagName") === "SELECT") {
      return field.val() !== "" && !field.val().startsWith("Sélectionnez");
    }
    return field.val().trim() !== "";
  }

  function displayError(field, key) {
    if (field.length) {
      field.addClass("is-invalid");
      let errorMessage = $(`#${field.attr("id")}_error`);
      if (!errorMessage.length) {
        // Créer un message d'erreur si inexistant
        errorMessage = $("<div>", {
          id: `${field.attr("id")}_error`,
          class: "invalid-feedback",
        });
        field.parent().append(errorMessage);
      }
      errorMessage.text(`Le champ ${formatLabel(key)} est obligatoire.`);
    }
  }

  function formatLabel(key) {
    const labels = {
      statut: "Statut",
      enseignant: "Enseignant",
      filiere: "Filière",
      semestre: "Semestre",
      date_debut: "Date début EDT",
      date_fin: "Date fin EDT",
      nh_programme: "N/H Programmé",
      heures_supp: "Heures Supp",
      grade: "Grade",
      heures_dues: "Heures Dues",
    };
    return labels[key] || key.replace(/_/g, " ");
  }
});
