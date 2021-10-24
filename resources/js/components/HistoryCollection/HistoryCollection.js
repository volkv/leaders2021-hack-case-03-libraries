import React, { useEffect, useState, useCallback } from 'react';
import WidgetCollection from '../WidgetCollection';
import Loader from '../Loader';

import { useRequestedCollection } from '../../utils/hooks';

const HistoryCollection = ({ id }) => {
	const { items, initial, error, requested } = useRequestedCollection(`/api/v1/history_for_user_id/${id}`, 100, id);

	const placeholder =  error ? error : ( requested ? 'К сожалению не найдена ваша история книг' : 'Для отображения вашей читательской истории пожалуйста укажите ID читателя' );
	const title = 'Прочитанное вами';

	return ( 
		<>
			<WidgetCollection
				title={title}
				items={items}
				child='Book'
				placeholder={initial ? <span className='placeholder'>{ placeholder }</span> : <Loader />} 
			/>
		</>	
	);
};

export default HistoryCollection;