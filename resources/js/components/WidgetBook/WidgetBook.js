import React from 'react';
import BookImage from './BookImage';

const WidgetBook = () => {
	return (
		<div className='book'>
			<BookImage />
			<span className=''>book title</span>
			<span className=''>author</span>
		</div>
	);
};

export default WidgetBook;