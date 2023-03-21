const postLogin = async (URL, user) => {
    try {
        const response = await fetch(URL, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(user)
        });

        const data = await response.json();
        console.log(data);
        return data; // Return the data to the caller
    } catch (error) {
        console.log(error);
        return null; // Return null if an error occurred
    }
};

export{
    postLogin
}