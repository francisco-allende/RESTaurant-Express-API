const getData = async (URL) => { 
	try {
		//addSpinner();
		let token = "?"
		const res = await fetch(URL, {
			headers: {
				'Authorization': `Bearer ${token}`
			  }
		});

		if (!res.ok) {
			throw new Error(`${res.status}-${res.statusText}`);
		}
		
		const data = await res.json();
		console.log(data)
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