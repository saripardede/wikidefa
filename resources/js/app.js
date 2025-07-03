import './bootstrap';

/* tutorial-detail */
document.addEventListener('DOMContentLoaded', () => {
    const readMoreBtn = document.getElementById('read-more-btn');
    if (readMoreBtn) {
        readMoreBtn.addEventListener('click', () => {
            document.getElementById('short-text').classList.add('hidden');
            document.getElementById('full-text').classList.remove('hidden');
            readMoreBtn.classList.add('hidden');
        });
    }
});

function confirmApproval() {
    document.getElementById('confirmPopup').classList.remove('hidden');
}

function closePopup() {
    document.getElementById('confirmPopup').classList.add('hidden');
}

function submitApproval() {
    const radios = document.getElementsByName('point');
    let point = null;
    for (let r of radios) {
        if (r.checked) point = r.value;
    }
    const custom = document.getElementById('customPoint').value;
    if (custom) point = custom;
    if (!point) {
        alert('Silakan pilih atau masukkan point.');
        return;
    }
    document.getElementById('noteInput').value = 'Poin diberikan: ' + point;
    document.getElementById('statusInput').value = 'approved';
    document.getElementById('statusForm').submit();
}

function promptNote(status) {
    document.getElementById('noteStatus').value = status;
    document.getElementById('notePopup').classList.remove('hidden');
}

function closeNotePopup() {
    document.getElementById('notePopup').classList.add('hidden');
}

function submitNote() {
    const note = document.getElementById('noteText').value;
    const status = document.getElementById('noteStatus').value;
    if (!note.trim()) {
        alert('Catatan tidak boleh kosong.');
        return;
    }
    document.getElementById('noteInput').value = note;
    document.getElementById('statusInput').value = status;
    document.getElementById('statusForm').submit();
}
