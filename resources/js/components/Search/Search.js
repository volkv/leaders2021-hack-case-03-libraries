import React, { useState } from 'react';
import { useHistory } from 'react-router-dom';

const Search = ({ match }) => {
	const history = useHistory();
	const [value, setValue] = useState(match.params?.query ?? '');

	const handleCommit = (event) => history.push(`/search/${event.target.value}`);

	const handleKeyDown = (event) => {
		if(event.keyCode != 13) return;
		handleCommit(event);
	};

	return (
		<div className='search-container'>
			<input value={value} onChange={(e) => setValue(e.target.value)} placeholder='Поиск книг, авторов, жанров...' onBlur={handleCommit} onKeyDown={handleKeyDown} />
		</div>
	);
};

export default Search;
