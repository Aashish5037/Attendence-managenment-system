@props(['url'])
<tr>
    <td class="header" style="text-align: center;">
        <a href="{{ $url ?? config('app.url') }}" style="display: inline-block;">
            <img src="https://imgs.search.brave.com/af5y6XfMUYBhxOsmR7Agr6KNaw3zHMm7xGpXlVlAM0o/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9wbmdp/bWcuY29tL3VwbG9h/ZHMvZW1haWwvc21h/bGwvZW1haWxfUE5H/MTYucG5n" alt="{{ config('app.name') }} Logo" style="height: 64px; width: auto; display: block; margin: 0 auto;">
        </a>
    </td>
</tr>