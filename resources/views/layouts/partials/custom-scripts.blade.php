<!-- filepath: resources/views/layouts/partials/custom-scripts.blade.php -->
<script>
    // Dark Mode Toggle
    const darkModeSwitch = document.getElementById('darkModeSwitch');

    if (localStorage.getItem('darkMode') === 'on') {
        document.body.classList.add('dark-mode');
        darkModeSwitch.checked = true;
    } else {
        darkModeSwitch.checked = false;
    }

    darkModeSwitch.addEventListener('change', function() {
        document.body.classList.toggle('dark-mode');
        if (document.body.classList.contains('dark-mode')) {
            localStorage.setItem('darkMode', 'on');
        } else {
            localStorage.setItem('darkMode', 'off');
        }
    });

    // Sidebar State
    const body = document.body;

    function saveSidebarState() {
        if (body.classList.contains('sidebar-collapse')) {
            localStorage.setItem('sidebarOpen', 'false');
        } else {
            localStorage.setItem('sidebarOpen', 'true');
        }
    }

    if (localStorage.getItem('sidebarOpen') === 'true') {
        body.classList.remove('sidebar-collapse');
    } else {
        body.classList.add('sidebar-collapse');
    }

    const observer = new MutationObserver(saveSidebarState);
    observer.observe(body, {
        attributes: true,
        attributeFilter: ['class']
    });

    // Toastr Configuration
    function toasterOptions() {
        toastr.options = {
            "closeButton": false,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-top-center",
            "preventDuplicates": true,
            "onclick": null,
            "showDuration": "100",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "show",
            "hideMethod": "hide"
        };
    }
    toasterOptions();
</script>
