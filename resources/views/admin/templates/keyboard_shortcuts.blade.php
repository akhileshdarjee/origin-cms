<div class="modal fade" id="keyboardShortcutsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-md">{{ __('Keyboard Shortcuts') }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <svg class="close-icon" width="12" height="12" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
                        <g>
                            <polygon points="512,59.076 452.922,0 256,196.922 59.076,0 0,59.076 196.922,256 0,452.922 59.076,512 256,315.076 452.922,512     512,452.922 315.076,256   "></polygon>
                        </g>
                    </svg>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered mb-0">
                    <tbody>
                        <tr>
                            <td width="40%"><kbd>Ctrl+G</kbd></td>
                            <td width="60%">{{ __('Open Universal Search') }}</td>
                        </tr>
                        <tr>
                            <td width="40%"><kbd>Ctrl+S</kbd></td>
                            <td width="60%">{{ __('Save Record') }}</td>
                        </tr>
                        <tr>
                            <td width="40%"><kbd>Ctrl+H</kbd></td>
                            <td width="60%">{{ __('Go to Home') }}</td>
                        </tr>
                        <tr>
                            <td width="40%"><kbd>Alt+M</kbd></td>
                            <td width="60%">{{ __('Show Modules') }}</td>
                        </tr>
                        <tr>
                            <td width="40%"><kbd>Alt+A</kbd></td>
                            <td width="60%">{{ __('See All Activity') }}</td>
                        </tr>
                        <tr>
                            <td width="40%"><kbd>Alt+P</kbd></td>
                            <td width="60%">{{ __('Open Profile') }}</td>
                        </tr>
                        <tr>
                            <td width="40%"><kbd>Alt+S</kbd></td>
                            <td width="60%">{{ __('Open Settings') }}</td>
                        </tr>
                        <tr>
                            <td width="40%"><kbd>Ctrl+Shift+R</kbd></td>
                            <td width="60%">{{ __('Clear Cache and Reload') }}</td>
                        </tr>
                        <tr>
                            <td width="40%"><kbd>Shift+/</kbd></td>
                            <td width="60%">{{ __('Show Keyboard Shortcuts') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
