document.querySelectorAll('[data-edit-toggle]').forEach(function (button) {
    button.addEventListener('click', function () {
        var targetId = button.getAttribute('data-edit-toggle');
        var panel = document.getElementById(targetId);

        if (!panel) {
            return;
        }

        var isHidden = panel.classList.contains('is-hidden');

        document.querySelectorAll('.task-edit-shell').forEach(function (shell) {
            shell.classList.add('is-hidden');
        });

        document.querySelectorAll('.edit-toggle').forEach(function (toggleButton) {
            toggleButton.classList.remove('active');
        });

        if (isHidden) {
            panel.classList.remove('is-hidden');
            button.classList.add('active');
        }
    });
});

document.querySelectorAll('[data-edit-close]').forEach(function (button) {
    button.addEventListener('click', function () {
        var targetId = button.getAttribute('data-edit-close');
        var panel = document.getElementById(targetId);
        var toggleButton = document.querySelector('[data-edit-toggle="' + targetId + '"]');

        if (panel) {
            panel.classList.add('is-hidden');
        }

        if (toggleButton) {
            toggleButton.classList.remove('active');
        }
    });
});
