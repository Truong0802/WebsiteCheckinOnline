function handleFileSelect(evt)
{
    let files = evt.target.files;
    let file = files[0];

    let reader = new FileReader();

    reader.onload = function(e)
    {
        let data = new Uint8Array(e.target.result);
        let workbook = XLSX.read(data, {
            type: 'array'
        });

        let worksheet = workbook.Sheets[workbook.SheetNames[0]];
        let jsonData = XLSX.utils.sheet_to_json(worksheet, {
            header: 1
        });

        let table = document.getElementById('excelTable');

        let headerRow = document.createElement('tr');

        //Hiển thị header
        for (let i = 0; i < 4; i++)
        {
            let th = document.createElement('th');
            th.textContent = jsonData[0][i];
            headerRow.appendChild(th);
        }
        let th4 = document.createElement('th');
        let th5 = document.createElement('th');
        let th6 = document.createElement('th');
        let th7 = document.createElement('th');
        th4.textContent = 'Ngày bắt đầu';
        th5.textContent = 'Ngày kết thúc';
        th6.textContent = 'Ngày học';
        th7.textContent = 'Giờ học';
        headerRow.appendChild(th4);
        headerRow.appendChild(th5);
        headerRow.appendChild(th6);
        headerRow.appendChild(th7);
        table.appendChild(headerRow);

        for (let i = 1; i < jsonData.length; i++)
        {
            let dataRow = document.createElement('tr');
            for (let j = 0; j < 3; j++)
            {
                let td = document.createElement('td');
                td.textContent = jsonData[i][j];
                dataRow.appendChild(td);
            }
            let dates = jsonData[i][3].split(' - ');
            for (let j = 0; j < 2; j++)
            {
                let td = document.createElement('td');
                td.textContent = dates[j];
                dataRow.appendChild(td);
            }
            let datesTime = jsonData[i][4].split(' - ');
            for (let j = 0; j < 2; j++)
            {
                let td = document.createElement('td');
                td.textContent = datesTime[j];
                dataRow.appendChild(td);
            }
            table.appendChild(dataRow);
        }
    };

    reader.readAsArrayBuffer(file);
}

document.getElementById('fileInput').addEventListener('change', handleFileSelect, false);
