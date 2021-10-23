import React from 'react';
import WidgetBook from '../WidgetBook';
import WidgetPerson from '../WidgetPerson';

const WidgetCollection = ({ 
	id,
	title,
	items,
	child,
	placeholder
}) => {
	const WidgetItem = ({ itemProps }) => {
		switch(child){
			case 'Person':
				return <WidgetPerson {...itemProps} />;
				break;
			case 'Book':
			default:
				return <WidgetBook {...itemProps} />;
				break;
		}
	}

	return (
		<div className='collection'>
			<h3>{ title }</h3>
			<div className='collection-items'>
				{ items.length > 0 ? 
					items.map((item, keyIndex) => (<WidgetItem key={`collection_${child}_${title}_${keyIndex}`} itemProps={item} />) )
					: placeholder
				}
			</div>
		</div>
	);
};

export default WidgetCollection;