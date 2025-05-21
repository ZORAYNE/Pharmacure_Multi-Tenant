<div id="{{ $id ?? 'modal' }}" class="modal" style="display:none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.5);">
    <div class="modal-content" style="background-color: #fefefe; margin: 10% auto; padding: 20px; border: 1px solid #888; width: 420px; border-radius: 8px; position: relative;">
        <span class="close" style="color: #aaa; position: absolute; right: 15px; top: 10px; font-size: 28px; font-weight: bold; cursor: pointer;">&times;</span>
        <div>
            {{ $slot }}
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var modal = document.getElementById('{{ $id ?? 'modal' }}');
        if (!modal) return;
        var closeBtn = modal.querySelector('.close');
        if (closeBtn) {
            closeBtn.addEventListener('click', function () {
                modal.style.display = 'none';
            });
        }

        window.addEventListener('click', function (event) {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });
    });
</script>
