<table>
    <tr>
        <td>Check Type</td>
        <td>{{ $ageVerification->getCheckType() }}</td>
    </tr>
    <tr>
        <td>Age</td>
        <td>{{ $ageVerification->getAge() }}</td>
    </tr>
    <tr>
        <td>Result</td>
        <td>{{ $ageVerification->getResult() ? 'true' : 'false' }}</td>
    </tr>
</table>