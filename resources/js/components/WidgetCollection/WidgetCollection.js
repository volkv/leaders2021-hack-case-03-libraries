import React from 'react';
import WidgetBook from '../WidgetBook';

const WidgetCollection = ({ id, title, items, placeholder }) => {
	return (
		<div className='collection'>
			<h3>{ title }</h3>
			<div className='collection-items'>
				{ items.length > 0 ? 
					items.map((item, keyIndex) => (<WidgetBook key={`collection_${title}_${keyIndex}`} {...item} />) )
					: placeholder
				}
			</div>
		</div>
	);
};

export default WidgetCollection;