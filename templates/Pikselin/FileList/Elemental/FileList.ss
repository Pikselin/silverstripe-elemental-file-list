<% if $Title && $ShowTitle %><h2 class="element__title">$Title</h2><% end_if %>

<% loop FileList %>
    <a class="download" href="$File.URL" target="_blank">
        <span class="download__icon">
            <svg class="icon download-svg">
            <use xlink:href="$resourceURL('pikselin/silverstripe-elemental-file-list:client/images/sprite.icons.svg')#arrow-down-download"></use>
            </svg>
        </span>
        <span>
            <span class="download__title">$Title</span>
            <span class="download__filemeta">
                <span class="filetype">$File.Extension.UpperCase</span>
                <span class="filesize">$Top.TidyFileSize($File.Size)</span>
            </span>
        </span>
    </a>
<% end_loop %>
