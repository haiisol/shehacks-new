<script>
    const CSRF = {
        name: <?= json_encode($this->security->get_csrf_token_name(), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>,
        hash: <?= json_encode($this->security->get_csrf_hash(), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>
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
        beforeSend: function(xhr, settings) {

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
        complete: function(xhr) {

            if (ajaxMethod === 'POST') {
                try {
                    let response = xhr.responseJSON || JSON.parse(xhr.responseText);

                    if (response && response.csrf_hash) {
                        CSRF.hash = response.csrf_hash;
                        document.querySelectorAll(`input[name="${CSRF.name}"]`)
                            .forEach(el => el.value = CSRF.hash);
                    }
                } catch(e) {
                    console.error('Invalid CSRF JSON:', e);
                }
            }
        }
    });

    $(document).on('show.bs.modal', '.modal', function() {
        const input = $(this).find(`input[name="${CSRF.name}"]`);
        input.val(CSRF.hash);
    });
</script>
