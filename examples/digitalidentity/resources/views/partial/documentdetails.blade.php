<table>
    <tr>
        <td>Type</td>
        <td>{{ $documentDetails->getType() }}</td>
    </tr>
    <tr>
        <td>Issuing Country</td>
        <td>{{ $documentDetails->getIssuingCountry() }}</td>
    </tr>
    <tr>
        <td>Document Number</td>
        <td>{{ $documentDetails->getDocumentNumber() }}</td>
    </tr>
    <tr>
        <td>Expiration Date</td>
        <td>{{ $documentDetails->getExpirationDate()->format('d-m-Y') }}</td>
    </tr>
</table>