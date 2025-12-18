<script>
    let csrfName = '<?= $this->security->get_csrf_token_name(); ?>', csrfHash = '<?= $this->security->get_csrf_hash(); ?>';
    let ajaxMethod = '';

    document.addEventListener("DOMContentLoaded", function () {
        const forms = document.querySelectorAll("form");

        forms.forEach(function (form) {
            if (!form.querySelector(`input[name="${csrfName}"]`)) {
                const csrfInput = document.createElement("input");
                csrfInput.type = "hidden";
                csrfInput.name = csrfName;
                csrfInput.value = csrfHash;

                form.appendChild(csrfInput);
            }
        });
    });

    $.ajaxSetup({
        beforeSend: function(xhr, settings) {
            
            ajaxMethod = settings.type;

            if (settings.type === 'POST') {
                if (typeof settings.data === 'string') {
                    settings.data += `&${csrfName}=${csrfHash}`;
                } else if (typeof settings.data === 'object') {
                    settings.data = settings.data || {};
                    settings.data[csrfName] = csrfHash;
                }
            }
            
        },
        complete: function(xhr) {
            
            if (ajaxMethod === 'POST') {
                try {
                    let response = xhr.responseJSON || JSON.parse(xhr.responseText);

                    if (response && response.csrf_hash) {
                        csrfHash = response.csrf_hash;
                        $('input[name="' + csrfName + '"]').val(csrfHash);
                    }
                } 
                catch (e) {
                    console.error('Error parsing AJAX response:', e);
                }
            }
        }
    });
    
    $(document).on('show.bs.modal', '.modal', function() {
        let input = $(this).find('input[name="' + csrfName + '"]');
        if (input.length > 0) {
            input.val(csrfHash);
        } 
    });
</script>