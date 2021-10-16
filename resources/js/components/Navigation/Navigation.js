import React from 'react';
import { Link } from 'react-router-dom';

const Navigation = () => {
	return (
		<div className='navigation'>
			<Link to="/">Главная</Link>
			<Link to="/libraries">Библиотеки</Link>
			<Link to="/books">Книги</Link>
			<Link to="/events">Мероприятия</Link>
			<Link to="/fav">Избранное</Link>	
		</div>

	);
};

export default Navigation;
