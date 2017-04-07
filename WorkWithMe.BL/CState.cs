using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using WorkWithMe.PL; 

namespace WorkWithMe.BL
{
   public class CState
    {
        public Guid Id { get; set; }
        public string StateName { get; set; }

        public CState()  {}

        public CState(Guid id, string stateName)
        {
            Id = id;
            StateName = stateName; 
        }

        public CState(string stateName)
        {
            StateName = stateName; 
        }

        public CState(Guid id)
        {
            Id = id; 
        }

        public void Create()
        {
            using (WorkWithMeDataContext oDC = new WorkWithMeDataContext())
            {
                tblState s = new tblState();
                s.Id = Guid.NewGuid();
                s.StateName = StateName;

                oDC.tblStates.InsertOnSubmit(s);
                oDC.SubmitChanges();
            }
        }


        public void Update()
        {
            using (WorkWithMeDataContext oDC = new WorkWithMeDataContext())
            {
                tblState state = (from s in oDC.tblStates where s.Id == Id select s).FirstOrDefault();

                state.Id = Id;
                state.StateName = StateName; 

                oDC.SubmitChanges();
            }
        }

        public void Delete()
        {
            using (WorkWithMeDataContext oDC = new WorkWithMeDataContext())
            {
                tblState state = (from s in oDC.tblStates where s.Id == Id select s).FirstOrDefault();

                oDC.tblStates.DeleteOnSubmit(state);
                oDC.SubmitChanges();
            }
        }
    }
}
