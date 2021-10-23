import React, { useState, useEffect } from 'react';
import WidgetCollection from '../WidgetCollection';
import Loader from '../Loader';

import { useRequestedCollection } from '../../utils/hooks';

const NeighboursCollection = ({ id }) => {
	const { items, initial, error, requested } = useRequestedCollection(`/api/v1/neighbours_for_user_id/${id}`, 10, id);

	const placeholder =  error ? error : ( requested ? 'Поздравляем! Ваш вкус уникален - никто не найден!' : 'Для отображения пользователей с схожими интересами введите ваш ID читателя' );
	const title = 'Совпадения с другими пользователями';

	return ( 
		<>
			<WidgetCollection
				title={title}
				items={items}
				child='Person'
				placeholder={initial ? <span className='placeholder'>{ placeholder }</span> : <Loader />} 
			/>
		</>	
	);
};

export default NeighboursCollection;
