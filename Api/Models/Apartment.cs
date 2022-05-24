using System;
using System.Collections.Generic;
using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Models
{
    public class Apartment
    {

        [Key]
        [Column("id")]
        public string Id { get; set; }

        [Column("name")]
        public string Name { get; set; }

        [Column("street")]
        public string Street { get; set; }

        [Column("zip_code")]
        public string ZipCode { get; set; }

        [Column("city")]
        public string City { get; set; }

        [Column("longitude")]
        public string Longitude { get; set; }

        [Column("latitude")]
        public string Latitude { get; set; }

        public List<Room> Rooms { get; set; } = new List<Room>();

    }
}
