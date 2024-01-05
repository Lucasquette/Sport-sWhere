mapboxgl.accessToken = 'pk.eyJ1IjoibHVjYXNxdWV0dGVzIiwiYSI6ImNscTd5dDRjNjFjY2cyamt6cTF5OWswc2EifQ.fGb8-XeCWUNgclOcz7HXHQ';
const map = new mapboxgl.Map({
  container: 'map',
  style: 'mapbox://styles/mapbox/streets-v11',
  center: [2.502266, 46.631157],
  zoom: 5
});

const nav = new mapboxgl.NavigationControl();
map.addControl(nav, 'top-right');
