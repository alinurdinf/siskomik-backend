@component('mail::message', ['mso' => 'mso-16'])
# Need Approval For {{ $maildata->name }}, Ref Number {{$maildata->reference_number}}

Mahasiswa atas nama {{ $maildata->name }} telah mengajaukan surat {{ $maildata->type }} dan membutuhkan
persetujuan Anda.

<table>
    <tr>
        <th style="text-align: left;">Nama Mahasiswa</th>
        <td>{{ $maildata->name }}</td>
    </tr>
    <tr>
        <th style="text-align: left;">Prodi</th>
        <td>{{ $maildata->prodi }}</td>
    </tr>
    <tr>
        <th style="text-align: left;">Semester</th>
        <td>{{ $maildata->semester }}</td>
    </tr>
    <tr>
        <th style="text-align: left;">Jenis Surat</th>
        <td>{{ $maildata->type }}</td>
    </tr>

</table>
<br />
Silakan klik tombol di bawah ini untuk menindaklanjuti permohonan registrasi training ini.

@component('mail::button', ['url' => route('incoming.show',$maildata->reference_number)])

Show Message
@endcomponent

@component('mail::button-split', [
'button' => [
[
'url' => 'mailto:' . $maildata->from. '?subject=Ask Requester:' . $maildata->reference_number . '&body=ASK About
Training Registration ID ' . $maildata->reference_number . ' %0DApproval Message:',
'slot' => 'ASK To Mahasiswa',
'color' => 'ask',
],
],
])
@endcomponent

Terima kasih
Hormat Saya, {{ $maildata->name }}
@endcomponent
