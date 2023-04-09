<!-- build:js assets/vendor/js/core.js -->
<script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
<script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/node-waves/node-waves.js') }}"></script>

<script src="{{ asset('assets/vendor/libs/hammer/hammer.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/i18n/i18n.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/typeahead-js/typeahead.js') }}"></script>

<script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
<!-- endbuild -->

<!-- Vendors JS -->
<script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/swiper/swiper.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/cleavejs/cleave.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js') }}"></script>

<!-- Main JS -->
<script src="{{ asset('assets/js/main.js') }}?v={{ time() }}"></script>
<script src="{{ asset('assets/js/blockUi.js') }}?v={{ time() }}"></script>
<script type="text/javascript">
    $('.submit').on('click',function () {
        $.blockUI({ message: null });
    });
    $('.btn-search').on('click',function () {
        $.blockUI({ message: null });
        $('#form-search').submit();
    });

    const numeralMask = document.querySelectorAll('.numbering');
    if (numeralMask.length) {
        numeralMask.forEach(e => {
            new Cleave(e, {
                delimiter: '',
                numeral: true
            });
        });
    }

    const numeralMaskDot = document.querySelectorAll('.numbering-dot');
    if (numeralMaskDot.length) {
        numeralMaskDot.forEach(e => {
            new Cleave(e, {
                numeral: true,
                numeralDecimalMark: ',',
                numeralDecimalScale: 0,
                delimiter: '.'
            });
        });
    }

    function formatNumber(number) {
        b = parseFloat(number);
        var _minus = false;
        if (b < 0) _minus = true;
        b = b.toString();
        b = b.replace(".", "");
        b = b.replace("-", "");
        c = "";
        panjang = b.length;
        jk = 0;
        for (i = panjang; i > 0; i--) {
            jk = jk + 1;
            if (((jk % 3) == 1) && (jk != 1)) {
                c = b.substr(i - 1, 1) + "." + c;
            } else {
                c = b.substr(i - 1, 1) + c;
            }
        }
        if (_minus) c = "-" + c;
        return c;
    }
</script>