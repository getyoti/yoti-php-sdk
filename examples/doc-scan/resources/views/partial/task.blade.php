<table class="table table-striped">
    <tbody>
        <tr>
            <td>ID</td>
            <td>{{ $task->getId() }}</td>
        </tr>
        <tr>
            <td>State</td>
            <td>
                <span class="badge badge-{{ $task->getState() == 'DONE' ? 'success' : 'secondary' }}">
                    {{ $task->getState() }}
                </span>
            </td>
        </tr>
        <tr>
            <td>Created</td>
            <td>{{ $task->getCreated()->format('r') }}</td>
        </tr>
        <tr>
            <td>Last Updated</td>
            <td>{{ $task->getLastUpdated()->format('r') }}</td>
        </tr>
    </tbody>
</table>