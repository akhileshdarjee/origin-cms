<footer class="main-footer">
    <div class="float-right d-none d-sm-inline">
        <i class="fas fa-code"></i> {{ __('with') }}
        <i class="fas fa-heart" style="color: #d90429;"></i> {{ __('by') }} 
        <strong>
            <a href="https://www.facebook.com/mr.multitalented" target="_blank" style="color: #676a6c;">
                Akhilesh Darjee
            </a>
        </strong>
    </div>
    Copyright &copy; {{ date('Y') }} - 
    <strong>
        <a href="https://github.com/akhileshdarjee/origin-cms" target="_blank" style="color: #676a6c;">
            {{ config('app.brand.name') }}
        </a>
    </strong>
</footer>
<a href="#" class="back-to-top">
    <i class="fa fa-chevron-up"></i>
</a>
<div id="fancybox" class="modal" style="display: none;">
    <span class="fancybox-close">&times;</span>
    <img class="fancybox-content" id="fancybox-img">
    <div id="fancybox-caption"></div>
</div>
