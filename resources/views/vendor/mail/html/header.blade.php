{{-- Header of every e-mail: the Sabonea logo, embedded in the e-mail by App\Listeners\EmbedMailLogo. --}}
@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
<img src="cid:{{ \App\Listeners\EmbedMailLogo::CID }}" alt="{{ config('app.name') }}" width="220" height="79" style="display: block; width: 220px; max-width: 100%; height: auto; border: 0;">
</a>
</td>
</tr>
