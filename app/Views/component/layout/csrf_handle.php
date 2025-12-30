<script>
    const CSRF = {
        name: <?= json_encode(csrf_token(), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>,
        hash: <?= json_encode(csrf_hash(), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>
    };

    let ajaxMethod = '';

    document.addEventListener("DOMContentLoaded", function () {
        const forms = document.querySelectorAll("form");
        forms.forEach(form => {
            if (!form.querySelector(`input[name="${CSRF.name}"]`)) {
                const input = document.createElement("input");
                input.type = "hidden";
                input.name = CSRF.name;
                input.value = CSRF.hash;
                form.appendChild(input);
            }
        });
    });

    $.ajaxSetup({
        beforeSend: function (xhr, settings) {

            ajaxMethod = settings.type;

            if (settings.type === 'POST') {
                if (typeof settings.data === 'string') {
                    settings.data += `&${encodeURIComponent(CSRF.name)}=${encodeURIComponent(CSRF.hash)}`;
                } else if (typeof settings.data === 'object') {
                    settings.data = settings.data || {};
                    settings.data[CSRF.name] = CSRF.hash;
                }
            }
        },
        complete: function (xhr) {

            if (ajaxMethod === 'POST') {
                try {
                    let response = xhr.responseJSON || JSON.parse(xhr.responseText);

                    if (response && response.csrf_hash) {
                        CSRF.hash = response.csrf_hash;
                        document.querySelectorAll(`input[name="${CSRF.name}"]`)
                            .forEach(el => el.value = CSRF.hash);
                    }
                } catch (e) {
                    console.error('Invalid CSRF JSON:', e);
                }
            }
        }
    });

    $(document).on('show.bs.modal', '.modal', function () {
        const input = $(this).find(`input[name="${CSRF.name}"]`);
        input.val(CSRF.hash);
    });

    $(document).on("ajaxSuccess", function (event, xhr) {
        let res;

        try {
            res = xhr.responseJSON ?? JSON.parse(xhr.responseText);
        } catch (_) {
            return;
        }

        if (res?.csrf_name && res?.csrf_hash) {
            CSRF.name = res.csrf_name;
            CSRF.hash = res.csrf_hash;

            document.querySelectorAll('input[type="hidden"]').forEach(el => {
                if (el.name !== CSRF.name && el.name.startsWith('csrf')) {
                    el.remove();
                }
            });

            document.querySelectorAll('form').forEach(form => {
                let input = form.querySelector(`input[name="${CSRF.name}"]`);
                if (!input) {
                    input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = CSRF.name;
                    form.appendChild(input);
                }
                input.value = CSRF.hash;
            });
        }
    });

</script>