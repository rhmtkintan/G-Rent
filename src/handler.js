const mysql = require('mysql');

const connection = mysql.createConnection({
  host: 'localhost',
  user: 'root',
  port: '3307',
  password: '',
  database: 'rental_api',
});

connection.connect((err) => {
  if (err) {
    console.error('Koneksi ke MySQL gagal: ' + err.stack);
    return;
  }
  console.log('Terhubung ke MySQL dengan ID koneksi ' + connection.threadId);
});

const addBookingHandler = (request, h) => {
    return new Promise((resolve, reject) => {
      const { customer_name, car_id, pickup_date, return_date } = request.payload;
      const id = nanoid(16);
      const createdAt = new Date().toISOString();
      const updatedAt = createdAt;
  
      const newBooking = {
        customer_name,
        car_id,
        pickup_date,
        return_date,
        id,
        createdAt,
        updatedAt,
      };
  
      const query = 'INSERT INTO booking SET ?';
  
      connection.query(query, newBooking, (error, results) => {
        if (error) {
          reject(error);
        } else {
          const isSuccess = results.affectedRows > 0;
          if (isSuccess) {
            resolve({
              status: 'success',
              message: 'Booking berhasil ditambahkan',
              data: {
                bookingId: id,
              },
            });
          } else {
            resolve({
              status: 'fail',
              message: 'Booking gagal ditambahkan',
            });
          }
        }
      });
    });
  };

const getAllBookingsHandler = () => {
  return new Promise((resolve, reject) => {
    const query = 'SELECT * FROM booking';

    connection.query(query, (error, results) => {
      if (error) {
        reject(error);
      } else {
        resolve({
          status: 'success',
          data: {
            bookings: results,
          },
        });
      }
    });
  });
};

const getBookingByIdHandler = (request, h) => {
  return new Promise((resolve, reject) => {
    const { id } = request.params;
    const query = 'SELECT * FROM booking WHERE id_booking = ?';

    connection.query(query, [id], (error, results) => {
      if (error) {
        reject(error);
      } else {
        if (results.length > 0) {
          resolve({
            status: 'success',
            data: {
              booking: results[0],
            },
          });
        } else {
          resolve({
            status: 'fail',
            message: 'Booking tidak ditemukan',
          });
        }
      }
    });
  });
};

const updateBookingByIdHandler = (request, h) => {
  return new Promise((resolve, reject) => {
    const { id } = request.params;
    const { konfirmasi_pembayaran,id_booking } = request.payload;
    const updatedAt = new Date().toISOString();

    const query =
      'UPDATE booking SET konfirmasi_pembayaran= ? WHERE id_booking = ?';

    connection.query(
      query,
      [konfirmasi_pembayaran,id],
      (error, results) => {
        if (error) {
          reject(error);
        } else {
          const isSuccess = results.affectedRows > 0;
          if (isSuccess) {
            resolve({
              status: 'success',
              message: 'Booking berhasil diperbarui',
            });
          } else {
            resolve({
              status: 'fail',
              message: 'Gagal memperbarui booking. ID'+{id_booking}+'tidak ditemukan',
            });
          }
        }
      }
    );
  });
};

const deleteBookingByIdHandler = (request, h) => {
  return new Promise((resolve, reject) => {
    const { id } = request.params;
    const query = 'DELETE FROM booking WHERE id_booking = ?';

    connection.query(query, [id], (error, results) => {
      if (error) {
        reject(error);
      } else {
        const isSuccess = results.affectedRows > 0;
        if (isSuccess) {
          resolve({
            status: 'success',
            message: 'Booking berhasil dihapus',
          });
        } else {
          resolve({
            status: 'fail',
            message: 'Gagal menghapus booking. ID tidak ditemukan',
          });
        }
      }
    });
  });
};

const getContactInfoHandler = () => {
    return new Promise((resolve, reject) => {
      const query = 'SELECT * FROM infoweb';
  
      connection.query(query, (error, results) => {
        if (error) {
          reject(error);
        } else {
          resolve({
            status: 'success',
            data: {
              contact: results,
            },
          });
        }
      });
    });
  };

  const updateContactInfoHandler = (request, h) => {
    return new Promise((resolve, reject) => {
      const {id, nama_rental, telp, alamat, email, no_rek } = request.payload;
      const query =
        'UPDATE infoweb SET nama_rental = ?, telp = ?, alamat = ?, email = ?, no_rek = ? WHERE id = 1';
  
      connection.query(
        query,
        [nama_rental, telp, alamat, email, no_rek, id],
        (error, results) => {
          if (error) {
            reject(error);
          } else {
            const isSuccess = results.affectedRows > 0;
            if (isSuccess) {
              resolve({
                status: 'success',
                message: 'Informasi kontak berhasil diperbarui',
              });
            } else {
              resolve({
                status: 'fail',
                message: 'Gagal memperbarui informasi kontak',
              });
            }
          }
        }
      );
    });
 };
  
  const getAllGadgetsHandler = () => {
    return new Promise((resolve, reject) => {
      const query = 'SELECT * FROM gadget';
  
      connection.query(query, (error, results) => {
        if (error) {
          reject(error);
        } else {
          resolve({
            status: 'success',
            data: {
              gadgets: results,
            },
          });
        }
      });
    });
  };
  
  const getGadgetByIdHandler = (request, h) => {
    return new Promise((resolve, reject) => {
      const { id } = request.params;
      const query = 'SELECT * FROM gadget WHERE id_gadget = ?';
  
      connection.query(query, [id], (error, results) => {
        if (error) {
          reject(error);
        } else {
          if (results.length > 0) {
            resolve({
              status: 'success',
              data: {
                gadget: results[0],
              },
            });
          } else {
            resolve({
              status: 'fail',
              message: 'Gadget tidak ditemukan',
            });
          }
        }
      });
    });
  };


module.exports = {
    getContactInfoHandler,
  getAllBookingsHandler,
  getBookingByIdHandler,
  addBookingHandler,
  updateBookingByIdHandler,
  deleteBookingByIdHandler,
  updateContactInfoHandler,
  getAllGadgetsHandler,
  getGadgetByIdHandler,
};