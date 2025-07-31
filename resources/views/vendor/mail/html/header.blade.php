@props(['url'])
<tr>
    <td class="header" style="text-align: center;">
        <a href="{{ $url ?? config('app.url') }}" style="display: inline-block;">
            <img src="https://images.fineartamerica.com/images/artworkimages/mediumlarge/2/email-envelope-csa-images.jpg" alt="{{ config('app.name') }} Logo" style="height: 64px; width: auto; display: block; margin: 0 auto;">
        </a>
    </td>
</tr>