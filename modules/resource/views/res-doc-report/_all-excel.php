<table class="table table-bordered table-striped" style='margin-top:10px;width: 100%'>
            <thead>
                <tr>
                    <th style='text-align:center'> ประเภทหวย</th>
                    <th style='text-align:center'>ตัวเลข</th>
                    <th style='text-align:center'>จำนวนเงิน</th>
                    
                </tr>
            </thead>
            <tbody>               
                <tr ng-repeat="line in result">
                    <td>{{ line.type }}</td>
                    <td align='right'>{{ line.number }}</td>
                    <td align='right'>{{ line.amount | number }}</td>
                  
                </tr>
                <tr>
                    <td>รวม</td>
                    <td></td>
                    <td align='right'>{{ sum | number }} </td>
                    
                </tr>
            </tbody>
        </table>