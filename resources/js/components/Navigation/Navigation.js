import React from 'react';
import { Link } from 'react-router-dom';

const Navigation = () => {
	return (
		<div className='navigation'>
			<Link to="/">Главная</Link>
			<Link to="/about">Библиотеки</Link>
			<Link to="/catalog">Книги</Link>
			<Link to="/events">Мероприятия</Link>
			<Link to="/fav">Избранное</Link>	
		</div>

	);
};

export default Navigation;
