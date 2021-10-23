import { useState, useEffect } from 'react'

export const useRequestedCollection = (url, max, observed) => {
	const [requested, setRequested] = useState(false);
	const [initial, setInitial] = useState(true);
	const [error, setError] = useState('');
	const [items, setItems] = useState([]);
	const [startTime, setStartTime] = useState(0);
	const [endTime, setEndTime] = useState(0);

	useEffect(() => {
		if (observed) {
			setInitial(false);
			setRequested(false);
			var st_time = performance.now();
			fetch(url)
				.then((response) => {
					if(response.ok){
						return response.json()
					}
					setInitial(true);
					setError('При запросе возникла ошибка');
					throw new Error('При запросе возникла ошибка : ' + response.status);
				})
				.then((data) => {
					setEndTime(performance.now());
					setError('');
					setRequested(true)
					if(data.results && data.results.length > 0){
						setItems(data.results
							.slice(0, max));
					} else if (data.length > 0) {
						setItems(data
							.slice(0, max));
					} else {
						setInitial(true);
						setItems([]);
					}
				})
				.catch((error) => {
					console.log(error);
				})
			setStartTime(st_time);
		} else {
			if (!initial || items.length || error) {
				setRequested(false);
				setInitial(true);
				setItems([]);
				setError('');
			}
		}
	}, [observed]);

	return {
		items,
		initial,
		error,
		requested,
		elapsed: endTime-startTime
	}
}