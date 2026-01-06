<script>
    const CSRF = {
        name: <?= json_encode(csrf_token()) ?>,
        hash: <?= json_encode(csrf_hash()) ?>
    };

    function updateCSRF(name, hash) {
        if (!name || !hash) return;

        CSRF.name = name;
        CSRF.hash = hash;

        document.querySelectorAll(`input[name="${CSRF.name}"]`)
            .forEach(el => el.value = CSRF.hash);
    }

    $.ajaxPrefilter(function(options, originalOptions, jqXHR) {

        if (!options.type || options.type.toUpperCase() !== 'POST') return;

        if (options.data instanceof FormData) {
            options.data.set(CSRF.name, CSRF.hash);
            return;
        }

        if (typeof options.data === 'string') {
            options.data +=
                (options.data ? '&' : '') +
                encodeURIComponent(CSRF.name) +
                '=' +
                encodeURIComponent(CSRF.hash);
        } else {
            options.data = options.data || {};
            options.data[CSRF.name] = CSRF.hash;
        }
    });

    $(document).ajaxComplete(function(event, xhr) {
        let res;

        try {
            res = xhr.responseJSON ?? JSON.parse(xhr.responseText);
        } catch {
            return;
        }

        if (res?.csrf_name && res?.csrf_hash) {
            updateCSRF(res.csrf_name, res.csrf_hash);
        }
    });

    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('form').forEach(form => {
            if (!form.querySelector(`input[name="${CSRF.name}"]`)) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = CSRF.name;
                input.value = CSRF.hash;
                form.appendChild(input);
            }
        });
    });

    $(document).on('show.bs.modal', '.modal', function() {
        $(this)
            .find(`input[name="${CSRF.name}"]`)
            .val(CSRF.hash);
    });
</script>