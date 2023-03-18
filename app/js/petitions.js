const getData = async (URL) => { 
	try {
		//addSpinner();
		let token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2NzkwOTU5MjUsImV4cCI6MjIwNDY5NTkyNSwiYXVkIjoiNDI0ZDY3YWFiYzA2MjYyYjYxOTdlZWE3ZDA2MTEzMDkyNmQxMWMyNiIsImRhdGEiOnsiaWQiOjEsInVzZXJuYW1lIjoic295X3NvY2lvNSIsInBhc3N3b3JkIjoiJDJ5JDEwJGpELmNGMWtQRXpOWTFIOHNCUGVnYy5UUm5EdXg5eVVsRUQyWUpJUlhkWEZuRkJPMnZcL1E1NiIsImlzQWRtaW4iOiJzaSIsInJvbCI6InNvY2lvIiwiZmVjaGFfaW5pY2lvIjoiMjAyMy0wMy0xNyIsImZlY2hhX2ZpbmFsIjpudWxsfSwiYXBwIjoiTGEgQ29tYW5kYSJ9.n3wdWoB-b6KHV-1DXInnnAS06jVK-KJbV_mk-OsIsSU"
		const res = await fetch(URL, {
			headers: {
				'Authorization': `Bearer ${token}`
			  }
		});

		if (!res.ok) {
			throw new Error(`${res.status}-${res.statusText}`);
		}
		
		const data = await res.json();
		return data;
	} catch (err) {
		console.error(err);
	}finally{
		//destroySpinner();
	}
};

export{
    getData
}