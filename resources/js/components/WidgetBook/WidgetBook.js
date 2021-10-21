import React from 'react';
import BookImage from './BookImage';

const WidgetBook = ({
	title,
	author
}) => {
	return (
		<div className='book'>
			<BookImage />
			<span className='title'>{ title }</span>
			<span className='author'>{ author }</span>
		</div>
	);
};

export default WidgetBook;