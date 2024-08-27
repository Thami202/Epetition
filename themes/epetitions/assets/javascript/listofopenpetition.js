$(document).ready(function() {
    var petitionsTable = $('#petitions-table').dynatable({
      writers: {
        _rowWriter: customRowWriter
      }
    })
  })

  
  
  // Custom row writer makes each row clickable
  // Custom row writer makes each row clickable
function customRowWriter(rowIndex, record, columns, cellWriter) {
  var tr = '';

  // loop through the columns in the same order as the table headers
  for (let i = 0, len = columns.length; i < len; i++) {
    const columnId = columns[i].id;
    const columnValue = record[columnId];
    tr += `<td>${columnValue}</td>`;
  }

  return `<tr style="cursor:pointer" onclick="window.location.href='petition/${record.id}'">${tr}</tr>`;
}


 
 // Custom row writer makes each row clickable
  /*function customRowWriter(rowIndex, record, columns, cellWriter) {
      var tr = '';
  
      // grab the record's attribute for each column 
      for (let i = 1, len = columns.length; i < len; i++) {
        tr +=   mycellWriter(columns[i], record);
      }
     
  
      return '<tr style="cursor:pointer">' + tr + '</tr>';
  }
   */
 

  function mycellWriter(column, record) {
      return `<td onclick="window.location.href=\'petition/' + ${record.id} + '\'">${column.attributeWriter(record)}</td>`;
  }