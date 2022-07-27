
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="mb-4 row" style="margin-left: 5px;">
                <div class="col-sm-auto">
                    <b>Tool Id :</b> {{$tool->id}}
                </div>
                <div class="col-sm-auto">
                    <b>Tool Name :</b> {{$tool->name}}
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-stripped table-hover">
                    <tr>
                        <th>Date</th>
                        <th>Description</th>
                    </tr>
                    @foreach ($histories as $history)
                        <tr>
                            <td>{{ $history->created_at->format(dateTimeFormat()) }}</td>
                            <td>
                                <?php $description = explode('Comments: ', $history->description);
                                echo $description[0];
                                if (isset($description[1]))
                                {
                                    echo '<br/><i><b>Comments:</b> '.$description[1].'</i>';
                                }
                                ?>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</div>
