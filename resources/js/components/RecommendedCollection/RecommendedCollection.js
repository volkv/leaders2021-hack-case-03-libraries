import React, { useEffect, useState, useCallback } from 'react';
import WidgetCollection from '../WidgetCollection';
import Loader from '../Loader';

let start = 0;
let end = 0;

const RecommendedCollection = ({ id }) => {
	const [requested, setRequested] = useState(false);
	const [initial, setInitial] = useState(true);
	const [error, setError] = useState('');
	const [items, setItems] = useState([]);

	useEffect(() => {
		if (id) {
			setInitial(false);
			setRequested(false);
			start = performance.now();
			fetch(`/api/v1/rec/${id}`)
				.then((response) => response.json())
				.then((data) => {
					setError('');
					setRequested(true)
					if(data.length > 0) {
						end = performance.now();
						setItems(data
							.slice(0,10)
							.map((i) => ({
								...i, author: `${i.factor}%`
							})));
					} else {
						setInitial(true);
						setItems([])
					}
				})
				.catch((error) => {
					console.log(error);
					setError('При запросе возникла ошибка');
				})
		} else {
			if (!initial || items.length || error) {
				setRequested(false);
				setInitial(true);
				setItems([]);
				setError('');
			}
		}
	}, [id]);

	const placeholder =  error ? error : ( requested ? 'К сожалению для вас не найдены рекоммендованные книги' : 'Для отображения рекоммендованных для вас книг пожалуйста укажите ID читателя' );
	const title = items.length > 0 ? `Рекоммендованные ( за ${end - start} мс)` : 'Рекоммендованные';

	return ( 
		<>
			<WidgetCollection
				title={title}
				items={items}
				placeholder={initial ? <span className='placeholder'>{ placeholder }</span> : <Loader />} 
			/>
		</>	
	);
};

export default RecommendedCollection;