$(document).ready(function() {
    var petitionsTable = $('#closedpetitions-table').dynatable({
      writers: {
        _rowWriter: customRowWriter
      }
    })
  })
  function customRowWriter(rowIndex, record, columns, cellWriter) {
    var tr = '';
  
    // loop through the columns in the same order as the table headers
    for (let i = 0, len = columns.length; i < len; i++) {
      const columnId = columns[i].id;
      const columnValue = record[columnId];
      tr += `<td>${columnValue}</td>`;
    }
  
    return `<tr style="cursor:pointer" onclick="window.location.href='detailpetition/${record.id}'">${tr}</tr>`;
  }