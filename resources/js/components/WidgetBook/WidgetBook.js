import React from 'react';
import BookImage from './BookImage';

const WidgetBook = ({
	title,
	author_name,
	cover_url
}) => {
	return (
		<div className='book'>
			<BookImage url={cover_url} />
			<span className='title'>{ title }</span>
			<span className='author'>{ author_name }</span>
		</div>
	);
};

export default WidgetBook;