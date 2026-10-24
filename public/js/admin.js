// Admin-only behaviour: live character counters and confirm dialogs for destructive forms.

document.querySelectorAll('[data-counter]').forEach(function (field) {
    var output = document.getElementById(field.dataset.counter);
    var max = parseInt(field.dataset.max, 10);

    function update() {
        var left = max - field.value.length;
        output.textContent = '(' + left + ' characters left)';
        output.classList.toggle('over', left < 0);
    }

    field.addEventListener('input', update);
    update();
});

document.querySelectorAll('form[data-confirm]').forEach(function (form) {
    form.addEventListener('submit', function (event) {
        if (!window.confirm(form.dataset.confirm)) {
            event.preventDefault();
        }
    });
});
