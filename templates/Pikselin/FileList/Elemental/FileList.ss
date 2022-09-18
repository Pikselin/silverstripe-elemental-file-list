<% if $Title && $ShowTitle %><h2 class="element__title">$Title</h2><% end_if %>

<% loop FileList %>
    <a class="download" href="$File.URL" target="_blank">
        <span class="download__icon">

        </span>
        <span>
            <span class="download__title">$File.Title</span>
            <span class="download__filemeta">
                <span class="filetype">$File.Extension</span>
                <span class="filesize">$Top.TidyFileSize($File.Size)</span>
            </span>
        </span>
    </a>
<% end_loop %>
