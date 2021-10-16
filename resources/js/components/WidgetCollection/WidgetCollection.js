import React from 'react';
import WidgetBook from '../WidgetBook';

const WidgetCollection = ({ id, title, items }) => {
	return (
		<div className='collection'>
			<h3>{ title }</h3>
			<div className='collection-items'>
				{ items.map((item, keyIndex) => (<WidgetBook key={`collection_${title}_${keyIndex}`} {...item} />) )}
			</div>
		</div>
	);
};

export default WidgetCollection;