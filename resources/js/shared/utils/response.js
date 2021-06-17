export const is404 = function(err) {
    return isErrorwithResponseAndStatus(err) && err.response.status === 404;
};

export const is422 = function(err) {
    return  isErrorwithResponseAndStatus(err) && err.response.status === 422;
};


const isErrorwithResponseAndStatus = function(err) {
    return err.response && err.response.status;
}   