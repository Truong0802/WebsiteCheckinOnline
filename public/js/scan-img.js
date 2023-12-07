function uploadAndConvert()
{
	var input = document.getElementById('uploadInput');
	var file = input.files[0];

	if (file)
    {
		var formData = new FormData();
		formData.append('file', file);

		var xhr = new XMLHttpRequest();

		xhr.addEventListener("readystatechange", function()
        {
			if (this.readyState === this.DONE)
            {
				if (this.status === 200)
                {
					const responseData = JSON.parse(this.responseText);
					const result = responseData.result[0].prediction[0].cells;

					const column1Data = result.filter(cell => cell.row !== null && cell.row > 1 && cell.col === 1).map(cell => cell.text.replace(/\|/g, '')).map(text => text.trim());
					console.log(column1Data);
					const column2Data = result.filter(cell => cell.row !== null && cell.row > 1 && cell.col === 2).map(cell => cell.text.replace(/\|/g, '')).map(text => text.trim());
					console.log(column2Data);
					const column3Data = result.filter(cell => cell.row !== null && cell.row > 1 && cell.col === 3).map(cell => cell.text.replace(/\|/g, '')).map(text => text.trim());
					console.log(column3Data);
					const column4Data = result.filter(cell => cell.row !== null && cell.row > 1 && cell.col === 4).map(cell => cell.text.replace(/\|/g, '')).map(text => text.trim());
					console.log(column4Data);

					displayTable(column1Data, column2Data, column3Data, column4Data);

					// displayTable(column1Data, column2Data, column4Data);
				}
			}
		});

		xhr.open("POST", "https://app.nanonets.com/api/v2/OCR/Model/9fba9e5b-da85-4738-99c8-2a0e59569d68/LabelUrls/?async=false");
		xhr.setRequestHeader("authorization", "Basic " + btoa("9512a5a6-8743-11ee-85c8-f6ad82d5eab7:"));

		xhr.send(formData);
	}
    else
    {
		alert("Chọn hình ảnh chứa danh sách điểm danh sinh viên để scan");
	}
}

// function displayTable(column1Data, column2Data, column3Data, column4Data)
// {
//     const tableBody = document.getElementById('tableBody');
//     tableBody.innerHTML = '';

//     const rowCount = Math.max(column1Data.length, column2Data.length, column3Data.length, column4Data.length);

//     for (let i = 0; i < rowCount; i++)
//     {
//         const newRow = document.getElementById('tableBody');
//         const input = document.createElement('input').insertRow();

//         const sttCell = input.insertCell(0);
//         sttCell.textContent = i + 1;

//         const maSVCell = input.insertCell(1);
//         maSVCell.textContent = column1Data[i] || '';

//         const hoTenCell = input.insertCell(2);
//         hoTenCell.textContent = column2Data[i] || '';

//         const ngaySinhCell = input.insertCell(3);
//         ngaySinhCell.textContent = column3Data[i] || '';

//         const tenLopCell = input.insertCell(4);
//         tenLopCell.textContent = column4Data[i] || '';
//         for (let j = 5; j <= 21; j++)
//         {
//             input.insertCell(j).textContent = '';
//         }
//     }
// }

function displayTable(column1Data, column2Data, column3Data, column4Data)
// function displayTable(column1Data, column2Data, column4Data)
{
	const tableBody = document.getElementById('tableBody');
	tableBody.innerHTML = '';

	const rowCount = Math.max(column1Data.length, column2Data.length, column3Data.length, column4Data.length);
	// const rowCount = Math.max(column1Data.length, column2Data.length, column4Data.length);

	for (let i = 0; i < rowCount; i++)
    {
		const newRow = document.createElement('tr');

		const sttCell = document.createElement('td');
		sttCell.textContent = i + 1;
		newRow.appendChild(sttCell);

		const maSVCell = document.createElement('td');
		const input1 = document.createElement('input');
		input1.type = 'text';
		input1.value = column1Data[i];
		input1.name = 'student_info_MSSV[]';
		input1.style = 'text-align: center';
		maSVCell.appendChild(input1);
		newRow.appendChild(maSVCell);

		const hoTenCell = document.createElement('td');
		const input2 = document.createElement('input');
		input2.type = 'text';
		input2.value = column2Data[i];
		input2.name = 'student_info_Student_Name[]';
		input2.style = 'width:210px; text-align: center;';
		hoTenCell.appendChild(input2);
		newRow.appendChild(hoTenCell);

		const ngaySinhCell = document.createElement('td');
		const input3 = document.createElement('input');
		input3.type = 'text';
		input3.value = column3Data[i];
		input3.name = 'student_info_Birthday[]';
		input3.style = 'text-align: center';
		ngaySinhCell.appendChild(input3);
		newRow.appendChild(ngaySinhCell);

		const tenLopCell = document.createElement('td');
		const input4 = document.createElement('input');
		input4.type = 'text';
		input4.value = column4Data[i];
		input4.name = 'student_info_Class[]';
		input4.style = 'text-align: center';
		tenLopCell.appendChild(input4);
		newRow.appendChild(tenLopCell);

		for (let j = 3; j < 10; j++)
        {
            if(j === 2)
            {
                const cell = document.createElement('td');
                cell.textContent = '';
                newRow.appendChild(cell);
            }
		}

		tableBody.appendChild(newRow);
        const form = document.querySelector('form');
        form.action = "/Confirm-to-scan";
	}
}


//Ban cán sự
function uploadAndConvert2()
{
	var input = document.getElementById('uploadInput');
	var file = input.files[0];

	if (file)
    {
		var formData = new FormData();
		formData.append('file', file);

		var xhr = new XMLHttpRequest();

		xhr.addEventListener("readystatechange", function()
        {
			if (this.readyState === this.DONE)
            {
				if (this.status === 200)
                {
					const responseData = JSON.parse(this.responseText);
					const result = responseData.result[0].prediction[0].cells;

					const column1Data = result.filter(cell => cell.row !== null && cell.row > 1 && cell.col === 1).map(cell => cell.text.replace(/\|/g, '')).map(text => text.trim());
					console.log(column1Data);
					const column2Data = result.filter(cell => cell.row !== null && cell.row > 1 && cell.col === 2).map(cell => cell.text.replace(/\|/g, '')).map(text => text.trim());
					console.log(column2Data);
					const column3Data = result.filter(cell => cell.row !== null && cell.row > 1 && cell.col === 3).map(cell => cell.text.replace(/\|/g, '')).map(text => text.trim());
					console.log(column3Data);
					const column4Data = result.filter(cell => cell.row !== null && cell.row > 1 && cell.col === 4).map(cell => cell.text.replace(/\|/g, '')).map(text => text.trim());
					console.log(column4Data);

					displayTable2(column1Data, column2Data, column3Data, column4Data);

					// displayTable(column1Data, column2Data, column4Data);
				}
			}
		});

		xhr.open("POST", "https://app.nanonets.com/api/v2/OCR/Model/9fba9e5b-da85-4738-99c8-2a0e59569d68/LabelUrls/?async=false");
		xhr.setRequestHeader("authorization", "Basic " + btoa("9512a5a6-8743-11ee-85c8-f6ad82d5eab7:"));

		xhr.send(formData);
	}
    else
    {
		alert("Chọn hình ảnh chứa danh sách điểm danh sinh viên để scan");
	}
}

function displayTable2(column1Data, column2Data, column3Data, column4Data)
// function displayTable(column1Data, column2Data, column4Data)
{
	const tableBody = document.getElementById('tableBody2');
	tableBody.innerHTML = '';

	const rowCount = Math.max(column1Data.length, column2Data.length, column3Data.length, column4Data.length);
	// const rowCount = Math.max(column1Data.length, column2Data.length, column4Data.length);

	for (let i = 0; i < rowCount; i++)
    {
		const newRow = document.createElement('tr');

		const sttCell = document.createElement('td');
		sttCell.textContent = i + 1;
		newRow.appendChild(sttCell);

		const maSVCell = document.createElement('td');
		const input1 = document.createElement('input');
		input1.type = 'text';
		input1.value = column1Data[i];
		input1.name = 'student_info_MSSV[]';
		input1.style = 'text-align: center';
		maSVCell.appendChild(input1);
		newRow.appendChild(maSVCell);

		const hoTenCell = document.createElement('td');
		const input2 = document.createElement('input');
		input2.type = 'text';
		input2.value = column2Data[i];
		input2.name = 'student_info_Student_Name[]';
		input2.style = 'width:210px; text-align: center;';
		hoTenCell.appendChild(input2);
		newRow.appendChild(hoTenCell);

		const ngaySinhCell = document.createElement('td');
		const input3 = document.createElement('input');
		input3.type = 'text';
		input3.value = column3Data[i];
		input3.name = 'student_info_Birthday[]';
		input3.style = 'text-align: center';
		ngaySinhCell.appendChild(input3);
		newRow.appendChild(ngaySinhCell);

		const tenLopCell = document.createElement('td');
		const input4 = document.createElement('input');
		input4.type = 'text';
		input4.value = column4Data[i];
		input4.name = 'student_info_Class[]';
		input4.style = 'text-align: center';
		tenLopCell.appendChild(input4);
		newRow.appendChild(tenLopCell);

		for (let j = 3; j < 10; j++)
        {
            if(j === 2)
            {
                const cell = document.createElement('td');
                cell.textContent = '';
                newRow.appendChild(cell);
            }
		}

		tableBody.appendChild(newRow);
        const form = document.querySelector('form');
        form.action = "/Add-ban-can-su";
	}
}
