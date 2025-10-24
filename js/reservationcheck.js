function processReservation(){
    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const phone = document.getElementById('phone').value;
    const date = document.getElementById('date').value;
    const time = document.getElementById('time').value;
    const guests = document.getElementById('guests').value;

    let errors = [];

    // Name validieren (mindestens 2 Zeichen)
    if (name.length < 2) {
        errors.push('Der Name muss mindestens 2 Zeichen haben.');
    }

    // E-Mail validieren (RegEx für E-Mail)
    const emailPattern = /^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/i;
    if (!emailPattern.test(email)) {
        errors.push('Bitte geben Sie eine gültige E-Mail-Adresse ein.');
    }

    // Telefonnummer validieren (nur Ziffern, mindestens 7 Zeichen)
    const phonePattern = /^[0-9]{7,}$/;
    if (!phonePattern.test(phone)) {
        errors.push('Die Telefonnummer muss aus mindestens 7 Ziffern bestehen.');
    }

    // Datum und Zeit validieren (darf nicht in der Vergangenheit liegen)
    const now = new Date();
    const reservationDateTime = new Date(`${date}T${time}`);
    if (reservationDateTime < now) {
        errors.push('Das Datum und die Uhrzeit dürfen nicht in der Vergangenheit liegen.');
    }

    // Validierung für Reservierungen am Folgetag: Reservierung muss vor 22:00 Uhr erfolgen
    const today = new Date();
    const reservationDate = new Date(date);
    if (reservationDate.getDate() === today.getDate() + 1) {
        if (today.getHours() >= 22) {
            errors.push('Reservierungen für den Folgetag müssen vor 22:00 Uhr erfolgen.');
        }
    }

    // Anzahl der Gäste validieren (muss mindestens 1 sein)
    if (guests < 1) {
        errors.push('Die Anzahl der Gäste muss mindestens 1 sein.');
    }

    return errors;
}

function submitReservation(){
    let reservation = document.getElementById("virt-reservation");
    let form = document.getElementById("order-submission");
    let errors = processReservation();
    if(errors == null) {
        form.submit();
    }
    form.submit();


}

// Event Listener für das Formular
document.getElementById('reservation-form').addEventListener('submit', function(event) {
    event.preventDefault(); // Verhindert das automatische Abschicken des Formulars

    const errorMessage = document.getElementById('error-message');
    
    // Validierung aufrufen
    const errors = validateReservationForm();

    // Fehler anzeigen oder das Formular abschicken
    if (errors.length > 0) {
        errorMessage.textContent = errors.join(' ');
    } else {
        errorMessage.textContent = '';  // Fehlermeldungen löschen
        alert('Reservierung erfolgreich!');
        // Hier könnte das Formular abgesendet werden
        // z.B.: this.submit();
    }
});