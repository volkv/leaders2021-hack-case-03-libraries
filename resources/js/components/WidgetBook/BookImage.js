import React from 'react';

const BookImage = ({ url }) => {

	return (
		url ? <img src={url} /> :  	
		<svg width="150" height="225" viewBox="0 0 150 225" fill="none" xmlns="http://www.w3.org/2000/svg">
			<rect width="150" height="225" rx="2" fill="#E5E5E5"/>
			<rect x="7" width="2" height="225" fill="#C4C4C4"/>
			<rect x="9" width="2" height="225" fill="#F0F0F0"/>
		</svg>
	);
};

export default BookImage;